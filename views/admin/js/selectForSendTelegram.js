const selectForSend = () => {

    const blockInfo = document.querySelector('.block_info .post')
    const posts = document.querySelectorAll('.posts_lists .post')
    const title = document.querySelector('#title')
    const mainText = document.querySelector('#main_text')
    const link = document.querySelector('#link')



    posts.forEach(function (elem) {
        elem.addEventListener('click', function () {
            alert('Эта новость будет добавлена в телеграм.Подтверждаете?')


            title.value = elem.childNodes[3].innerHTML
            mainText.value = elem.childNodes[5].innerHTML
            link.value = elem.childNodes[7].childNodes[1].value

        })
    })


}


export default selectForSend
