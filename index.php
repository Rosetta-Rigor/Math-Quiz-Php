<?php
session_start();

// Initialize placeholders
$quizStarted = isset($_SESSION['quiz_started']) ? $_SESSION['quiz_started'] : false;
$quizEnded = isset($_SESSION['quiz_ended']) ? $_SESSION['quiz_ended'] : false;

$question = $quizStarted ? $_SESSION['current_question'] : "0 * 0 = 0";
$answers = $quizStarted ? $_SESSION['current_answers'] : ["A", "B", "C", "D"];
$score = $_SESSION['score'] ?? 0;
$questions_left = $_SESSION['questions_left'] ?? 0;
$remarks = $_SESSION['remarks'] ?? "";

// Handle end of quiz remarks
if ($quizEnded) {
    echo "<script>alert('$remarks');</script>";
    session_destroy(); // Reset session on quiz end
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math Quiz</title>
</head>
<body>
    <h1><?php echo $question; ?></h1>

    <div>
        <?php foreach ($answers as $key => $answer): ?>
            <button onclick="submitAnswer('<?php echo $answer; ?>')"><?php echo $answer; ?></button>
        <?php endforeach; ?>
    </div>

    <p>Score: <?php echo $score; ?></p>
    <p>Questions Left: <?php echo $questions_left; ?></p>

    <div>
        <button onclick="location.href='quiz_logic.php?action=start'">Start Quiz</button>
        <button onclick="location.href='quiz_logic.php?action=close'">Close</button>
        <button onclick="location.href='settings.php'">Settings</button>
    </div>

    <script>
        function submitAnswer(answer) {
            location.href = `quiz_logic.php?action=answer&value=${answer}`;
        }
    </script>
</body>
</html>
