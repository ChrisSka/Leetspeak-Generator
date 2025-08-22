<?php
function toLeetSpeak($text, $level = 'mittel') {
    $levels = [
        'leicht' => ['a'=>'4','e'=>'3','i'=>'!','l'=>'1','o'=>'0','s'=>'5','t'=>'7'],
        'mittel' => ['a'=>'4','b'=>'8','e'=>'3','g'=>'6','i'=>'!','l'=>'1','o'=>'0','s'=>'5','t'=>'7','z'=>'2'],
        'hardcore' => [
            'a'=>'4','b'=>'8','c'=>'<','d'=>'|)','e'=>'3','f'=>'ph','g'=>'6','h'=>'#','i'=>'!','j'=>'_|','k'=>'|<','l'=>'1',
            'm'=>'|\\/|','n'=>'|\\|','o'=>'0','p'=>'|D','q'=>'(,)','r'=>'|2','s'=>'5','t'=>'7','u'=>'|_|','v'=>'\\/','w'=>'\\/\\/',
            'x'=>'><','y'=>'`/','z'=>'2'
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

function fromLeetSpeak($leetText) {
    $reverseMap = [
        '|\\/|' => 'm', '|\\|' => 'n', '|2' => 'r', '|)' => 'd', '|D' => 'p',
        '<' => 'c', 'ph' => 'f', '#' => 'h', '3' => 'e', '6' => 'g',
        '4' => 'a', '8' => 'b', '0' => 'o', '5' => 's', '7' => 't',
        '2' => 'z', '1' => 'l', '!' => 'i'
    ];

    $leetText = strtolower($leetText);

    uksort($reverseMap, function($a, $b) {
        return strlen($b) <=> strlen($a);
    });

    foreach ($reverseMap as $leet => $char) {
        $leetText = str_replace($leet, $char, $leetText);
    }

    return ucfirst($leetText);
}

// Eingaben
$input = $_POST['text'] ?? '';
$selectedLevel = $_POST['level'] ?? 'mittel';
$direction = $_POST['direction'] ?? 'toleet';

if ($input) {
    $output = $direction === 'toleet'
        ? toLeetSpeak($input, $selectedLevel)
        : fromLeetSpeak($input);
}
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

    <div class="card">
        <form method="post">
            <label for="direction">Übersetzungsrichtung:</label>
            <select name="direction" id="direction" onchange="toggleLevelSelect(this.value)">
                <option value="toleet" <?= $direction === 'toleet' ? 'selected' : '' ?>>🔡 Normal → Leet</option>
                <option value="fromleet" <?= $direction === 'fromleet' ? 'selected' : '' ?>>🧠 Leet → Normal</option>
            </select>

            <label for="text">Text eingeben:</label>
            <textarea name="text" id="text" rows="4" required><?= htmlspecialchars($input) ?></textarea>

            <div id="levelSelect">
                <label for="level">Leet-Level wählen:</label>
                <select name="level" id="level">
                    <option value="leicht" <?= $selectedLevel === 'leicht' ? 'selected' : '' ?>>😎 Leicht</option>
                    <option value="mittel" <?= $selectedLevel === 'mittel' ? 'selected' : '' ?>>🧠 Mittel</option>
                    <option value="hardcore" <?= $selectedLevel === 'hardcore' ? 'selected' : '' ?>>🤯 Hardcore</option>
                </select>
            </div>

            <input type="submit" value="Übersetzen">
        </form>
    </div>

    <?php if (!empty($output)): ?>
        <div class="card output">
            <button class="copy-btn" onclick="copyToClipboard()">📋 Kopieren</button>
            <span class="copy-msg" id="copyMsg">✅ Kopiert!</span>
            <h2>Ergebnis:</h2>
            <code id="leetOutput"><?= htmlspecialchars($output) ?></code>
        </div>
    <?php endif; ?>

    <script>
        function toggleLevelSelect(direction) {
            document.getElementById('levelSelect').style.display = direction === 'toleet' ? 'block' : 'none';
        }
        toggleLevelSelect("<?= $direction ?>");

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
