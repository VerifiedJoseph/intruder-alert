export class Message
{
	static error(text, hideAfter = false)
	{
		var error = document.getElementById('error')
		error.classList.remove('hide')
		error.innerText = text

		if (hideAfter === true) {
			setTimeout(() => {
				error.classList.add('hide')
			  }, 5000);
		}
	}
}
