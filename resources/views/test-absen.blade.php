<!DOCTYPE html>
<html>

<head>
    <title>Upload Foto Profile</title>
</head>

<body>
    <form action="{{ route('test.update.profile') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="name">Name:</label>
        <input type="text" name="name" id="name">
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email">
        <br>
        <label for="profile_image">Profile Image:</label>
        <input type="file" name="profile_image" id="profile_image">
        <br>
        <button type="submit">Submit</button>
    </form>
</body>

</html>
