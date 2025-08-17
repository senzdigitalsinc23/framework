<!DOCTYPE html>
<html>
<head>
    <title>Create Student</title>
</head>
<body>
    <h1>Add New Student</h1>
    <a href="/students">Back to List</a>

    <?php if (!empty($errors)): ?>
        <div style="color:red;">
            <ul>
                <?php foreach ($errors as $field => $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="/students" method="POST">
        <label>First Name: <input type="text" name="first_name" value="<?= htmlspecialchars($old['first_name'] ?? '') ?>"></label><br>
        <label>Last Name: <input type="text" name="last_name" value="<?= htmlspecialchars($old['last_name'] ?? '') ?>"></label><br>
        <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>"></label><br>
        <!-- Add other fields as needed -->
        <button type="submit">Save</button>
    </form>
</body>
</html>
