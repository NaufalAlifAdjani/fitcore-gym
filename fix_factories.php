<?php
$files = glob(__DIR__ . '/app/Models/*.php');
foreach($files as $file) {
    $content = file_get_contents($file);
    if(strpos($content, 'extends Model') !== false && strpos($content, 'use HasFactory;') === false) {
        $content = str_replace("use Illuminate\Database\Eloquent\Model;", "use Illuminate\Database\Eloquent\Factories\HasFactory;\nuse Illuminate\Database\Eloquent\Model;", $content);
        $content = preg_replace('/(class [a-zA-Z0-9_]+ extends Model\s*\{)/', "$1\n    use HasFactory;\n", $content);
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
