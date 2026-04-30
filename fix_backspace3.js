const fs = require('fs');
const path = require('path');

const filePath = path.join(process.env.APPDATA, 'npm', 'node_modules', '@aethelics', 'deepseek-code', 'dist', 'cli.mjs');

let content = fs.readFileSync(filePath, 'utf8');
console.log('File size:', content.length);

// The known position of the baA backspace handling
// Current content at 3250596: \b (backslash + b, 2 chars)
// We need to replace \b (literal backslash+b) with actual backspace byte 0x08

const BS = String.fromCharCode(0x08);
const DEL = String.fromCharCode(0x7f);

// Find the exact location by looking for backspace in baA region
const backspaceIdx = content.indexOf('backspace', 3250000);
console.log('backspace at:', backspaceIdx);

// Get 100 chars before
const context = content.substring(backspaceIdx - 100, backspaceIdx + 30);
console.log('Full context bytes:', context.split('').map(c => c.charCodeAt(0).toString(16).padStart(2,'0')).join(' '));

// Find the literal \b (0x5C 0x62) before backspace
for (let i = backspaceIdx - 50; i < backspaceIdx; i++) {
  if (content.charCodeAt(i) === 0x5c && content.charCodeAt(i+1) === 0x62) {
    console.log(`Found literal \\b at byte position ${i}`);
    console.log(`Bytes around: ${content.charCodeAt(i-2).toString(16)} ${content.charCodeAt(i-1).toString(16)} ${content.charCodeAt(i).toString(16)} ${content.charCodeAt(i+1).toString(16)} ${content.charCodeAt(i+2).toString(16)} ${content.charCodeAt(i+3).toString(16)}`);
    
    // Replace the two bytes 0x5C 0x62 with one byte 0x08
    const before = content.substring(0, i);
    const after = content.substring(i + 2);
    content = before + BS + after;
    
    fs.writeFileSync(filePath, content, 'utf8');
    console.log('✓ Replaced literal \\b with actual backspace byte (0x08)');
    
    // Verify
    const verify = fs.readFileSync(filePath, 'utf8');
    const vIdx = verify.indexOf('backspace', 3250000);
    const vContext = verify.substring(vIdx - 30, vIdx + 70);
    console.log('Verification:', JSON.stringify(vContext));
    break;
  }
}
