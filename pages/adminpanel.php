<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Admin Panel</title>
</head>
<style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 90%;
    max-width: 800px;
    padding: 20px;
}

.profile-section {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.profile-photo {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-right: 20px;
}

.description-box {
    flex: 1;
}

.description-box h2 {
    margin-bottom: 10px;
    font-size: 1.5em;
}

.description-box p {
    color: #666666;
}

.create-post-btn {
    background-color: #28a745;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    font-size: 1em;
    border-radius: 5px;
    cursor: pointer;
    margin-bottom: 20px;
}

.create-post-btn:hover {
    background-color: #218838;
}

.logout-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 10px 20px;
    background-color: #f44336; /* Red */
    color: white;
    border: none;
    text-decoration: none;
    cursor: pointer;
    border-radius: 5px;
    font-size: 16px;
        }
        
.logout-btn:hover {
            background-color: #c82333;
        }

.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: #ffffff;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
    border-radius: 10px;
    position: relative;
}

.close-btn {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    position: absolute;
    top: 10px;
    right: 20px;
    cursor: pointer;
}

.close-btn:hover,
.close-btn:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.todo-list {
    background-color: #f9f9f9;
    border: 1px solid #dddddd;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 20px;
}

.todo-list h4 {
    margin-bottom: 10px;
}

.todo-list ul {
    list-style-type: none;
    margin-bottom: 10px;
}

.todo-list ul li {
    background-color: #eeeeee;
    padding: 5px;
    margin-bottom: 5px;
    border-radius: 3px;
}

#ingredient-input {
    width: calc(100% - 100px);
    padding: 5px;
    border: 1px solid #cccccc;
    border-radius: 3px;
    margin-right: 10px;
}

#add-ingredient-btn {
    background-color: #007bff;
    color: #ffffff;
    border: none;
    padding: 5px 10px;
    border-radius: 3px;
    cursor: pointer;
}

#add-ingredient-btn:hover {
    background-color: #0056b3;
}

.description-box textarea {
    width: 100%;
    height: 100px;
    padding: 10px;
    border: 1px solid #cccccc;
    border-radius: 5px;
    margin-bottom: 20px;
}

.post-btn {
    background-color: #28a745;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    font-size: 1em;
    border-radius: 5px;
    cursor: pointer;
}

.post-btn:hover {
    background-color: #218838;
}

    </style>
<body>
    <div class="container">
        <div class="profile-section">
            <img src="man.png" alt="User Profile Photo" class="profile-photo">
            <div class="description-box">
               <h1> Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
               <p> Tap below button to create a post</p>
            </div>
        </div>
        <div><a href="index.html" class="logout-btn">Logout</a></div>
        <div class="post-section">
            <button class="create-post-btn" id="create-post-btn">Create a Post</button>
        </div>
    </div>

    
    <div class="modal" id="post-modal">
        <div class="modal-content">
            <span class="close-btn" id="close-btn">&times;</span>
            <h3>Create a Recipe Post</h3>
            <div class="todo-list">
                <h4>Ingredients List</h4>
                <ul id="ingredients-list">
                   
                </ul>
                <input type="text" id="ingredient-input" placeholder="Add an ingredient">
                <button id="add-ingredient-btn">Add</button>
            </div>
            <div class="description-box">
                <h4>Recipe Description</h4>
                <textarea id="recipe-description" placeholder="Describe the recipe process"></textarea>
            </div>
            <button class="post-btn" id="post-recipe-btn">Post</button>
        </div>
    </div>

    <script>
        document.getElementById('create-post-btn').addEventListener('click', function() {
            document.getElementById('post-modal').style.display = 'block';
        });

        document.getElementById('close-btn').addEventListener('click', function() {
            document.getElementById('post-modal').style.display = 'none';
        });

        document.getElementById('add-ingredient-btn').addEventListener('click', function() {
            const ingredientInput = document.getElementById('ingredient-input');
            const ingredientValue = ingredientInput.value.trim();
            if (ingredientValue !== '') {
                const li = document.createElement('li');
                li.textContent = ingredientValue;
                document.getElementById('ingredients-list').appendChild(li);
                ingredientInput.value = '';
            }
        });

        document.getElementById('post-recipe-btn').addEventListener('click', function() {
            const ingredients = document.getElementById('ingredients-list').innerHTML;
            const description = document.getElementById('recipe-description').value.trim();
            if (ingredients !== '' && description !== '') {
                // Here you can handle the post action, like sending data to the server
                alert('Recipe posted successfully!');
                document.getElementById('post-modal').style.display = 'none';
            } else {
                alert('Please add ingredients and a description.');
            }
        });
    </script>
</body>
</html>
