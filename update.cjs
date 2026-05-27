const fs = require('fs');
const path = require('path');

function walk(dir) {
    let results = [];
    const list = fs.readdirSync(dir);
    list.forEach(function(file) {
        file = dir + '/' + file;
        const stat = fs.statSync(file);
        if (stat && stat.isDirectory()) { 
            results = results.concat(walk(file));
        } else { 
            if(file.endsWith('.blade.php')) results.push(file);
        }
    });
    return results;
}

const files = walk('C:/Users/nicol/Desktop/tweb/laraProj0/resources/views');
files.forEach(file => {
    let content = fs.readFileSync(file, 'utf8');
    const search = `<script src="{{ asset('js/script.js') }}"></script>`;
    if(content.includes(search)) {
        const replace = `<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>\n    ` + search;
        content = content.replace(search, replace);
        fs.writeFileSync(file, content, 'utf8');
        console.log('Updated ' + file);
    }
});
