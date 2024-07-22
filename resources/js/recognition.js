import * as faceapi from 'face-api.js';

// Load face-api.js models
async function loadModels() {
    const MODEL_URL = '/models'; // URL to the directory where models are stored
    await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
    await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
    await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
}

// Start video stream from webcam
async function startVideo() {
    const video = document.getElementById('video');
    return new Promise((resolve, reject) => {
        navigator.mediaDevices.getUserMedia({ video: {} })
            .then(stream => {
                video.srcObject = stream;
                video.onloadedmetadata = () => {
                    video.play();
                    resolve(video);
                };
            })
            .catch(reject);
    });
}

// Detect faces from video and compare with stored descriptors
async function detectFace(video) {
    const canvas = faceapi.createCanvasFromMedia(video);
    document.body.append(canvas);
    const displaySize = { width: video.width, height: video.height };
    faceapi.matchDimensions(canvas, displaySize);

    // Load descriptors from the server
    const response = await fetch('/get-employee-descriptors');
    if (!response.ok) {
        console.error('Failed to load descriptors:', response.statusText);
        return;
    }

    const employeeDescriptors = await response.json();

    // Validate and create labeled descriptors
    const labeledDescriptors = employeeDescriptors.map(descriptor => {
        if (descriptor.label && Array.isArray(descriptor.descriptor) && descriptor.descriptor.length === 128) {
            return new faceapi.LabeledFaceDescriptors(descriptor.label, [new Float32Array(descriptor.descriptor)]);
        } else {
            console.warn('Invalid descriptor format:', descriptor);
            return null;
        }
    }).filter(descriptor => descriptor !== null); // Remove any invalid descriptors

    if (labeledDescriptors.length === 0) {
        console.error('No valid labeled descriptors found.');
        return;
    }

    // Create FaceMatcher instance
    const faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.6); // Adjust threshold as needed

    setInterval(async () => {
        const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptors();
        const resizedDetections = faceapi.resizeResults(detections, displaySize);
        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
        faceapi.draw.drawDetections(canvas, resizedDetections);
        faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);

        if (detections.length > 0) {
            const results = detections.map(d => faceMatcher.findBestMatch(d.descriptor));
            results.forEach(async (result, i) => {
                if (result.distance < 0.6) { // Adjust threshold as needed
                    console.log(`Face matched with employee ID: ${result.label}`);
                    // Send recognized employee ID to server
                    await fetch('/record-attendance', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ employee_id: result.label })
                    });
                }
            });
        }
    }, 1000);
}

// Initialize the face recognition process
async function initialize() {
    await loadModels();
    const video = await startVideo();
    detectFace(video);
}

initialize();
