<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>All Posts</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }
        .container {
            margin-top: 50px;
        }
        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #007bff;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 50px;
            transition: transform 0.3s ease;
        }
        .btn:hover {
            transform: scale(1.05);
        }
        .table {
            background-color: #ffffff;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .table th {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            font-weight: 600;
        }
        .table td {
            vertical-align: middle;
            text-align: center;
            font-size: 16px;
            color: #555;
        }
        .table img {
            border-radius: 10px;
            width: 100%;
            height: auto;
            max-width: 150px;
            transition: transform 0.3s ease;
        }
        .table img:hover {
            transform: scale(1.1);
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-warning {
            background-color: #ffc107;
            border: none;
        }
        .table-dark {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-between align-items-center mb-4">
            <div class="col-auto">
                <h1>All Posts</h1>
            </div>
            <div class="col-auto">
                <a href="/addposts" class="btn btn-primary">Add News Post</a>
                <button class="btn btn-danger" id="logoutBtn">Logout</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div id="postsContainer"></div>
            </div>
        </div>
    </div>

    <!-- Single Post Modal -->
    <div class="modal fade" id="singlePostModal" tabindex="-1" aria-labelledby="singlePostLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="singlePostLabel">Single Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Content will be dynamically inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector("#logoutBtn").addEventListener('click', function() {
            const token = localStorage.getItem('api_token');

            fetch('/api/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                window.location.href = "http://localhost:8000/";
            })
            .catch(error => console.error('Error logging out:', error));
        });

        function loadData() {
            const token = localStorage.getItem('api_token');
            
            fetch('/api/posts', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data); // Check the structure of the returned data

                const allPosts = data.data && Array.isArray(data.data.posts) ? data.data.posts : [];

                const postContainer = document.querySelector("#postsContainer");

                let tableData = `
                    <table class="table table-bordered">
                        <thead>
                            <tr class="table-dark">
                                <th>Image</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>View</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                if (allPosts.length > 0) {
            allPosts.forEach(post => {
                tableData += `
                    <tr>
                        <td><img src="/uploads/${post.image}" alt="${post.title}"></td>
                        <td>${post.title}</td>
                        <td>${post.description}</td>
                        <td><button type="button" class="btn btn-primary" data-bs-postid="${post.id}" data-bs-toggle="modal" data-bs-target="#singlePostModal">View</button></td>
                        <td><button type="button" class="btn btn-success" data-bs-postid="${post.id}">Update</button></td>
                        <td><button type="button" class="btn btn-danger" data-bs-postid="${post.id}">Delete</button></td>
                    </tr>
                `;
            });
        } else {
            tableData += `
                <tr>
                    <td colspan="6">No posts found.</td>
                </tr>
            `;
        }
                tableData += `
                    </tbody>
                </table>
                `;

                postContainer.innerHTML = tableData;
            })
            .catch(error => {
                console.error('Error fetching posts:', error);
            });
        }

        loadData();

        const singleModal = document.querySelector("#singlePostModal");
        if (singleModal) {
            singleModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const modalBody = document.querySelector("#singlePostModal .modal-body");

                const postId = button.getAttribute('data-bs-postid');
                const token = localStorage.getItem('api_token');

                fetch(`/api/posts/${postId}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const post = data.data && data.data.post;

                    if (post) {
                        modalBody.innerHTML = `
                            <strong>Title:</strong> ${post.title}<br>
                            <strong>Description:</strong> ${post.description}<br>
                            <img src="/uploads/${post.image}" width="150" alt="${post.title}">
                        `;
                    } else {
                        modalBody.innerHTML = 'Post not found.';
                    }
                })
                .catch(error => {
                    console.error('Error fetching post details:', error);
                });
            });
        }
    </script>
</body>
</html>
