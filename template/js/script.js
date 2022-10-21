setTimeout(() => {
	Array
		.from(document.querySelectorAll('input[data-action="login"]'))
		.forEach((node) => {
			node.addEventListener('click', () => {
				window
					.fetch('/rest/user/login/', {
						method: 'POST',
						body: new FormData(node.form)
					})
					.then(response => response.json())
					.then(data => {
						if (data.errors)
						{
							alert(Array.from(data.errors).map((error) => error.message).join(' - '));
						}
						else
						{
							window.location.href = '/';
						}
					})
				;
			})
		})
	;
	Array
		.from(
			document.querySelectorAll('[data-action="logout"]')
		)
		.forEach((node) => {
			console.log('node: ', node);

			node.addEventListener('click', (event) => {
				event.stopPropagation();
				event.preventDefault();
				window.fetch('/rest/user/logout/', {
					method: 'POST',
					body: (() => {
                        const formData = new FormData();
                        formData.append('tk', window.messageTk);
						return formData;
                    })()
				})
					.then(response => response.json())
					.then(data => {
						if (data.errors)
						{
							alert(Array.from(data.errors).map((error) => error.message).join(' - '));
						}
						else
						{
							window.location.href = '/';
						}
					})
				;
				return false;
			});
		})
	;
	const editForm = document.querySelector('form[data-action="edit-article"]');
	if (editForm)
	{
		editForm.addEventListener('submit', (event) => {
			event.stopPropagation();
			event.preventDefault();
			try
			{
				window.fetch('/rest/article/edit/{id}/'.replace('{id}', editForm.id.value || 'new') , {
					method: 'POST',
					body: new FormData(editForm)
				})
					.then(response => response.json())
					.then(data => {
						console.log('data: ', data);
						return;
						if (data.errors)
						{
							alert(Array.from(data.errors).map((error) => error.message).join(' - '));
						}
						else
						{
							window.location.href = '/articleread/{id}/'.replace('{id}', data['id']);
						}
					})
				;
			}
			catch (e)
			{
				console.log('e: ', e);

			}

			return false;
		});

	}
}, 0);
