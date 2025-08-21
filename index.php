<?php
function toLeetSpeak($text, $level = 'mittel') {
    $levels = [
        'leicht' => [
            'a' => '4', 'e' => '3', 'i' => '1', 'o' => '0', 's' => '5', 't' => '7',
        ],
        'mittel' => [
            'a' => '4', 'b' => '8', 'e' => '3', 'g' => '6', 'i' => '1', 'l' => '1',
            'o' => '0', 's' => '5', 't' => '7', 'z' => '2',
        ],
        'hardcore' => [
            'a' => '4', 'b' => '8', 'c' => '<', 'd' => '|)', 'e' => '3', 'f' => 'ph',
            'g' => '6', 'h' => '#', 'i' => '1', 'j' => '_|', 'k' => '|<', 'l' => '1',
            'm' => '|\\/|', 'n' => '|\\|', 'o' => '0', 'p' => '|D', 'q' => '(,)',
            'r' => '|2', 's' => '5', 't' => '7', 'u' => '|_|', 'v' => '\\/', 'w' => '\\/\\/',
            'x' => '><', 'y' => '`/', 'z' => '2',
        ],
    ];

    $map = $levels[$level] ?? $levels['mittel'];
    $text = strtolower($text);
    $leetText = '';

    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        $leetChar = $map[$char] ?? $char;
        $leetText .= strtoupper($leetChar);
    }

    return $leetText;
}

$input = $_POST['text'] ?? '';
$selectedLevel = $_POST['level'] ?? 'mittel';
$leetOutput = $input ? toLeetSpeak($input, $selectedLevel) : '';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Leet Speak Übersetzer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./style.css?v=<?= filemtime('./style.css') ?>">
</head>
<body>
<h1>🔤 Leet Speak Übersetzer</h1>
<form method="post">
    <label for="text">Gib deinen Text ein:</label>
    <textarea name="text" id="text" rows="4" required><?= htmlspecialchars($input) ?></textarea>
    <label for="level">Leet-Level wählen:</label>
    <select name="level" id="level">
        <option value="leicht" <?= $selectedLevel === 'leicht' ? 'selected' : '' ?>>😎 Leicht</option>
        <option value="mittel" <?= $selectedLevel === 'mittel' ? 'selected' : '' ?>>🧠 Mittel</option>
        <option value="hardcore" <?= $selectedLevel === 'hardcore' ? 'selected' : '' ?>>🤯 Hardcore</option>
    </select>
    <input type="submit" value="Übersetzen">
</form>
<?php if ($leetOutput): ?>
    <div class="output">
        <button class="copy-btn" onclick="copyToClipboard()">📋 Kopieren</button>
        <span class="copy-msg" id="copyMsg">✅ Kopiert!</span>
        <h2>Ergebnis:</h2>
        <p><strong>Original:</strong><br><?= nl2br(htmlspecialchars($input)) ?></p>
        <p><strong>Leet Speak (<?= htmlspecialchars($selectedLevel) ?>):</strong></p>
        <code id="leetOutput"><?= nl2br(htmlspecialchars($leetOutput)) ?></code>
    </div>
<?php endif; ?>
<script>
    function copyToClipboard() {
        const output = document.getElementById("leetOutput").innerText;
        navigator.clipboard.writeText(output).then(() => {
            const msg = document.getElementById("copyMsg");
            msg.style.display = "inline";
            setTimeout(() => msg.style.display = "none", 2000);
        });
    }
</script>
</body>
</html>