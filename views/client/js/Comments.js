export function Comments() {
    const createComment = document.querySelectorAll('.create_comment')
    const commentArea = document.querySelector('#comment')


    createComment.forEach(function (elem) {
        elem.addEventListener('click', function (e) {
            e.preventDefault()

            const postId = elem.getAttribute('data-post')

            let fD = new FormData()

            fD.append('comment', commentArea.value)

            commentArea.value = ''

            fetch(`/create_comment?post_id=${postId}`, {
                method: 'POST',
                body: fD,
            })
                .then(function (res){
                    return res.text()
                })
                .then(function (res){

                    if (res == 3 ){
                        alert('Коментарий успешно добавлен')
                        window.location.reload()
                    }
                })
        })
    })


}