<?php
session_start();

// Function to generate a new question
function generateQuestion()
{
    $operator = $_SESSION['operator'] ?? '+';
    $level = $_SESSION['level'] ?? 1;

    // Define number range based on level
    if ($level == 1) {
        $min = 1;
        $max = 10;
    } elseif ($level == 2) {
        $min = 11;
        $max = 100;
    } else {
        // Custom level
        $min = $_SESSION['custom_min'] ?? 1;
        $max = $_SESSION['custom_max'] ?? 10;
    }

    // Generate random numbers
    $num1 = rand($min, $max);
    $num2 = rand($min, $max);

    // Generate the correct answer based on the operator
    switch ($operator) {
        case '+':
            $correctAnswer = $num1 + $num2;
            break;
        case '-':
            $correctAnswer = $num1 - $num2;
            break;
        case '*':
            $correctAnswer = $num1 * $num2;
            break;
        default:
            $correctAnswer = $num1 + $num2; // Default to addition
    }

    // Store the current question and answer in the session
    $_SESSION['current_question'] = "$num1 $operator $num2 = ?";
    $_SESSION['correct_answer'] = $correctAnswer;

    // Generate random answer options
    $answers = [
        $correctAnswer,
        $correctAnswer + rand(1, 10),
        $correctAnswer - rand(1, 10),
        $correctAnswer + rand(11, 20),
    ];
    shuffle($answers);
    $_SESSION['current_answers'] = $answers;
}

// Handle actions
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'start':
        // Start the quiz
        $_SESSION['quiz_started'] = true;
        $_SESSION['score'] = 0;
        $_SESSION['wrong_answers'] = 0;
        $_SESSION['questions_left'] = $_SESSION['num_questions'] ?? 10;
        $_SESSION['quiz_ended'] = false;
        generateQuestion();
        header("Location: index.php");
        exit;

    case 'answer':
        if (!isset($_SESSION['quiz_started']) || !$_SESSION['quiz_started']) {
            header("Location: index.php");
            exit;
        }

        // Check submitted answer
        $userAnswer = intval($_GET['value']);
        if ($userAnswer === $_SESSION['correct_answer']) {
            $_SESSION['score']++;
        } else {
            $_SESSION['wrong_answers']++;
        }

        // Move to the next question or end the quiz
        $_SESSION['questions_left']--;
        if ($_SESSION['questions_left'] > 0) {
            generateQuestion();
        } else {
            // Calculate remarks
            $totalQuestions = $_SESSION['score'] + $_SESSION['wrong_answers'];
            $accuracy = ($_SESSION['score'] / $totalQuestions) * 100;

            if ($accuracy >= 90) {
                $_SESSION['remarks'] = "Excellent!";
            } elseif ($accuracy >= 75) {
                $_SESSION['remarks'] = "Good!";
            } elseif ($accuracy >= 50) {
                $_SESSION['remarks'] = "You can do better.";
            } elseif ($accuracy >= 30) {
                $_SESSION['remarks'] = "Please study harder.";
            } elseif ($accuracy >= 10) {
                $_SESSION['remarks'] = "Pick up a book.";
            } else {
                $_SESSION['remarks'] = "Quit school.";
            }

            $_SESSION['quiz_ended'] = true;
            header("Location: index.php");
            exit;
        }

        header("Location: index.php");
        exit;

    case 'close':
        // Close the quiz
        session_destroy();
        header("Location: index.php");
        exit;

    default:
        // Default redirect
        header("Location: index.php");
        exit;
}
?>
