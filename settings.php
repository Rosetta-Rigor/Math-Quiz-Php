<?php
session_start();

// Save settings if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['level'] = $_POST['level'];
    $_SESSION['operator'] = $_POST['operator'];
    $_SESSION['num_questions'] = intval($_POST['num_questions']);
    $_SESSION['custom_min'] = isset($_POST['custom_min']) ? intval($_POST['custom_min']) : 1;
    $_SESSION['custom_max'] = isset($_POST['custom_max']) ? intval($_POST['custom_max']) : 10;
    header("Location: index.php");
    exit;
}

// Get current settings for display
$currentLevel = $_SESSION['level'] ?? 1;
$currentOperator = $_SESSION['operator'] ?? '+';
$currentQuestions = $_SESSION['num_questions'] ?? 10;
$customMin = $_SESSION['custom_min'] ?? 1;
$customMax = $_SESSION['custom_max'] ?? 10;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
</head>
<body>
    <h1>Quiz Settings</h1>
    <form method="POST">
        <label for="level">Select Level:</label>
        <select name="level" id="level">
            <option value="1" <?php echo $currentLevel == 1 ? 'selected' : ''; ?>>Level 1 (1-10)</option>
            <option value="2" <?php echo $currentLevel == 2 ? 'selected' : ''; ?>>Level 2 (11-100)</option>
            <option value="custom" <?php echo $currentLevel == 'custom' ? 'selected' : ''; ?>>Custom</option>
        </select>

        <div id="custom-range" style="display: <?php echo $currentLevel == 'custom' ? 'block' : 'none'; ?>;">
            <label for="custom_min">Custom Min:</label>
            <input type="number" name="custom_min" id="custom_min" value="<?php echo $customMin; ?>">
            <label for="custom_max">Custom Max:</label>
            <input type="number" name="custom_max" id="custom_max" value="<?php echo $customMax; ?>">
        </div>

        <label for="operator">Select Operator:</label>
        <select name="operator" id="operator">
            <option value="+" <?php echo $currentOperator == '+' ? 'selected' : ''; ?>>+</option>
            <option value="-" <?php echo $currentOperator == '-' ? 'selected' : ''; ?>>-</option>
            <option value="*" <?php echo $currentOperator == '*' ? 'selected' : ''; ?>>*</option>
        </select>

        <label for="num_questions">Number of Questions:</label>
        <input type="number" name="num_questions" id="num_questions" value="<?php echo $currentQuestions; ?>" min="1">

        <button type="submit">Save</button>
    </form>

    <script>
        document.getElementById('level').addEventListener('change', function() {
            document.getElementById('custom-range').style.display = this.value === 'custom' ? 'block' : 'none';
        });
    </script>
</body>
</html>
