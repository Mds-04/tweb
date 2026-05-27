const fs = require('fs');
const files = [
    'resources/views/events/eventiMusicali.blade.php',
    'resources/views/events/eventiTeatrali.blade.php',
    'resources/views/events/manifestazioniLetterarie.blade.php',
    'resources/views/events/mostre.blade.php',
    'resources/views/events/convegni.blade.php'
];

files.forEach(file => {
    let content = fs.readFileSync(file, 'utf8');
    const formStart = '<form action="" method="GET"';
    const formEnd = '</form>';
    
    let startIndex = content.indexOf(formStart);
    let endIndex = content.indexOf(formEnd, startIndex);
    
    if (startIndex !== -1 && endIndex !== -1) {
        content = content.substring(0, startIndex) + content.substring(endIndex + formEnd.length);
        fs.writeFileSync(file, content, 'utf8');
        console.log('Removed form from ' + file);
    } else {
        console.log('Form not found in ' + file);
    }
});
