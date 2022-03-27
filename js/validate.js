const code = document.getElementById('code');
const addedDiv = document.getElementById('code-added-div');
const countedDiv = document.getElementById('code-counted-div');
const codeAdded = document.getElementById('code-added');
const codeCounted = document.getElementById('code-counted');

console.log(codeAdded.innerText);
console.log(codeCounted.innerText);

code.addEventListener('keyup', function(event) {

    if(code.value.length > 0) {
        if(code.value.toUpperCase() == codeAdded.innerText.toUpperCase()) {
            addedDiv.classList.add("warning");
        } else {
            addedDiv.classList.remove("warning");
        }
    
        if(code.value.toUpperCase() == codeCounted.innerText.toUpperCase()) {
            countedDiv.classList.add("warning");
        } else {
            countedDiv.classList.remove("warning");
        }
    }
});