<!DOCTYPE html>
<html>

<head>
    <title>Absensi Wajah</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script defer src="/js/face-api/face-api.min.js"></script>
    <style>
        #video {
            position: absolute;
            top: 0;
            left: 0;
        }

        #canvas {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }
    </style>
</head>

<body>
    <h1>Absensi Wajah</h1>
    <video id="video" width="720" height="560" autoplay muted></video>
    <canvas id="canvas" width="720" height="560"></canvas>
    <div id="info"></div>
    <script>
        const label = "{{ auth()->user()->employee->foto }}";
        const name = "{{ auth()->user()->name }}"
        const employeeImage = `/storage/employee/profile/${label}`;

        document.addEventListener('DOMContentLoaded', async () => {
            try {
                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                    faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                    faceapi.nets.ssdMobilenetv1.loadFromUri('/models')
                ]);

                console.log('Models loaded successfully');
                startVideo();
            } catch (error) {
                console.error('Error loading models:', error);
                alert('Error loading models. Check console for details.');
            }
        });

        function startVideo() {
            navigator.mediaDevices.getUserMedia({
                    video: {}
                })
                .then(stream => video.srcObject = stream)
                .catch(err => {
                    console.error("Error accessing webcam: ", err);
                    alert('Error accessing webcam. Check console for details.');
                });
        }

        video.addEventListener('play', async () => {
            const canvas = document.getElementById('canvas');
            const displaySize = {
                width: video.width,
                height: video.height
            };
            faceapi.matchDimensions(canvas, displaySize);

            try {
                const labeledFaceDescriptors = await loadLabeledImages();
                console.log('Labeled face descriptors loaded');
                const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);

                setInterval(async () => {
                    try {
                        const detections = await faceapi.detectAllFaces(video, new faceapi
                                .TinyFaceDetectorOptions())
                            .withFaceLandmarks().withFaceDescriptors();
                        const resizedDetections = faceapi.resizeResults(detections, displaySize);
                        const results = resizedDetections.map(d => faceMatcher.findBestMatch(d
                            .descriptor));

                        const ctx = canvas.getContext('2d');
                        ctx.clearRect(0, 0, canvas.width, canvas.height);

                        results.forEach((result, i) => {
                            const box = resizedDetections[i].detection.box;
                            const drawBox = new faceapi.draw.DrawBox(box, {
                                label: result.toString()
                            });
                            drawBox.draw(canvas);

                            if (result.label !== 'unknown') {
                                const employeeName = name;
                                console.log('Face matched:', employeeName);
                                showAlert(employeeName);
                            }
                        });
                    } catch (error) {
                        console.error('Error detecting faces:', error);
                        alert('Error detecting faces. Check console for details.');
                    }
                }, 100);
            } catch (error) {
                console.error('Error loading labeled images:', error);
                alert('Error loading labeled images. Check console for details.');
            }
        });

        async function loadLabeledImages() {
            try {
                const descriptions = [];
                const img = await faceapi.fetchImage(employeeImage);
                const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();
                if (detections) {
                    descriptions.push(detections.descriptor);
                } else {
                    console.error('No face detected in the image.');
                    alert('No face detected in the profile image.');
                }
                return [new faceapi.LabeledFaceDescriptors(name, descriptions)];
            } catch (error) {
                console.error('Error loading labeled images:', error);
                alert('Error loading labeled images. Check console for details.');
            }
        }

        function showAlert(employeeName) {
            document.getElementById('info').innerText = `Pegawai Teridentifikasi: ${employeeName}`;
        }
    </script>
</body>

</html>
