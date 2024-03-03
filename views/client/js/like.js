export function Like() {

    const likes = document.querySelector('.like')


    if (likes) {
        let singlePostAtr = document.querySelector('.single_post').getAttribute('data-id')
        let likedPost = document.querySelector('.liked_post')

        likes.addEventListener('click', handlerLike)

        function handlerLike() {

            if (!document.cookie.includes(`like${singlePostAtr}`)) {
                fetch(`/likes?post_id=${singlePostAtr}`, {method: 'GET'})
                    .then(response => response.json())
                    .then(data => {
                        likedPost.innerHTML = `Пост понравился: ${data.likesCount} пользователям`
                        alert(`Лайк поставлен`);
                        document.cookie = `like${singlePostAtr}`;
                    })
                    .catch(error => {
                        console.error('Ошибка при получении ID поста', error);
                    });
            } else {
                alert('Вы уже поставили лайк');
            }

        }


    }


}


