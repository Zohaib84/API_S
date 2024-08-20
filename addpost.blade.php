<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Create Post</title>
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }
        .container {
            margin-top: 50px;
        }
        .header-section {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .form-control {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-8 header-section mb-4">
                <h1>Create Post</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-8">
                <form id="addForm">
                    <div class="form-group">
                        <input type="text" id="title" class="form-control" placeholder="Title" required>
                    </div>
                    <div class="form-group">
                        <textarea id="description" class="form-control" rows="5" placeholder="Description" required></textarea>
                    </div>
                    <div class="form-group">
                        <input type="file" id="image" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="/allposts" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.querySelector("#addForm").onsubmit = async (e) => {
            e.preventDefault();

            const token = localStorage.getItem('api_token');
            const title = document.querySelector("#title").value;
            const description = document.querySelector("#description").value;
            const image = document.querySelector("#image").files[0]; // Corrected this line

            let formData = new FormData();
            formData.append('title', title);
            formData.append('description', description);
            formData.append('image', image);

            try {
                let response = await fetch('/api/posts', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Authorization': `Bearer ${token}`,
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok.');
                }

                let data = await response.json();
                console.log(data);

                // Redirect or show a success message
                window.location.href = "http://localhost:8000/allposts"; // Fixed URL
            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>
</body>
</html>
