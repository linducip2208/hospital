const fs = require('fs');
const path = require('path');

const filePath = path.join(process.env.APPDATA, 'npm', 'node_modules', '@aethelics', 'deepseek-code', 'dist', 'cli.mjs');

let content = fs.readFileSync(filePath, 'utf8');
console.log('File size:', content.length);

// The baA function - look for the backspace handling
// Original: if(A==="\x7f")return{name:"backspace", ...}
// Need to change to: if(A==="\b"||A==="\x7f")return{name:"backspace", ...}

// In the file as a string, \x7f (DEL) appears as the actual byte 0x7F
// \b (backspace) appears as the actual byte 0x08

// Let's find the exact position using the byte
const delChar = String.fromCharCode(0x7f);
const bsChar = String.fromCharCode(0x08);

// Find: if(A==="\x7f")return{name:"backspace"
const target = `if(A==="${delChar}")return{name:"backspace"`;
const idx = content.indexOf(target);
console.log('Found target at index:', idx);

if (idx >= 0) {
  // Show context
  console.log('Context before:', JSON.stringify(content.substring(idx - 10, idx)));
  console.log('Context match:', JSON.stringify(content.substring(idx, idx + target.length)));
  console.log('Context after:', JSON.stringify(content.substring(idx + target.length, idx + 80)));
  
  // Replace with: if(A==="\b"||A==="\x7f")return{name:"backspace"
  const replacement = `if(A==="${bsChar}"||A==="${delChar}")return{name:"backspace"`;
  
  content = content.replace(target, replacement);
  fs.writeFileSync(filePath, content, 'utf8');
  
  // Verify
  const newContent = fs.readFileSync(filePath, 'utf8');
  const newIdx = newContent.indexOf('backspace', 3250000);
  console.log('\nVerification:');
  console.log(JSON.stringify(newContent.substring(newIdx - 25, newIdx + 80)));
  
  console.log('\nPATCH SUKSES: baA function now handles both \\b (0x08) and DEL (0x7F) as backspace');
} else {
  console.log('TARGET NOT FOUND - checking alternatives...');
  
  // Check for already patched version
  const patchedTarget = `if(A==="${bsChar}"||A==="${delChar}")return{name:"backspace"`;
  const patchedIdx = content.indexOf(patchedTarget);
  if (patchedIdx >= 0) {
    console.log('Already patched at:', patchedIdx);
  } else {
    // Search for any backspace in the baA region
    const baIdx = content.indexOf('backspace', 3250000);
    console.log('Nearest backspace at', baIdx, ':', JSON.stringify(content.substring(baIdx - 30, baIdx + 60)));
  }
}
