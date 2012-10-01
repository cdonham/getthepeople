function wash( anInput ){
	if(anInput.value == anInput.defaultValue) anInput.value = '';
}
function checkWash( anInput ){
	if(anInput.value == '') anInput.value = anInput.defaultValue;
}