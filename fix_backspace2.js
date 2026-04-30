const fs = require('fs');
const path = require('path');

const filePath = path.join(process.env.APPDATA, 'npm', 'node_modules', '@aethelics', 'deepseek-code', 'dist', 'cli.mjs');

let content = fs.readFileSync(filePath, 'utf8');
console.log('File size:', content.length);

const DEL = String.fromCharCode(0x7f);
const BS = String.fromCharCode(0x08);

// Find current backspace handling in baA region
const idx = content.indexOf('backspace', 3250000);
console.log('Current baA backspace at', idx, ':');
console.log(JSON.stringify(content.substring(idx - 30, idx + 70)));

// The current text is: A==="\\b"||A==="?"  (where \\b is literal backslash-b, not 0x08)
// We need to fix it to: A==="\b"||A==="?"  (where \b is actual 0x08 byte)

// Let's construct the exact strings using raw bytes
const oldSegment = `A==="\\b"||A==="${DEL}`;
const newSegment = `A==="${BS}"||A==="${DEL}`;

console.log('\nOld segment (JSON):', JSON.stringify(oldSegment));
console.log('New segment (JSON):', JSON.stringify(newSegment));

const foundIdx = content.indexOf(oldSegment);
console.log('\nFound old segment at:', foundIdx);

if (foundIdx >= 0) {
  content = content.replace(oldSegment, newSegment);
  fs.writeFileSync(filePath, content, 'utf8');
  
  // Verify
  const newContent = fs.readFileSync(filePath, 'utf8');
  const newIdx = newContent.indexOf('backspace', 3250000);
  console.log('\nVerification:');
  console.log(JSON.stringify(newContent.substring(newIdx - 30, newIdx + 70)));
  console.log('\n✓ PATCH BERHASIL: baA function sekarang handle \\b (0x08) sebagai backspace');
} else {
  console.log('\nSegment not found. Let me check the raw bytes...');
  // Try to find the exact bytes
  for (let i = 3249000; i < 3251000; i++) {
    if (content[i] === '\\' && content[i+1] === 'b' && content[i+2] === '"') {
      console.log('Found literal \\b at', i, ':', JSON.stringify(content.substring(i-10, i+30)));
    }
  }
}
