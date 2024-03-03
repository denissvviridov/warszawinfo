export function helper() {

    let handlerColor = document.querySelector('.handler_color')


    const handlerColorTxt = () => {
        let description = document.querySelectorAll('.description, .single_description')
        let singleTitle = document.querySelectorAll('.single_title b')
        let singlePublish = document.querySelectorAll('.single_publish ')

         description.forEach(function (elem) {
             elem.style.color = 'white'
         })

        singleTitle.forEach(function (elem) {
            elem.style.background= 'white'
            elem.style.color= 'black'
        })
        singlePublish.forEach(function (elem) {
            elem.style.color = 'white'
        })
    }


    let position = false

    handlerColor.addEventListener('click', handlerChangeColorSite)

    function handlerChangeColorSite() {

        handlerColor.classList.toggle('handle');

        if (handlerColor.classList.contains('handle')) {
            position = true
            handlerColorTxt()
            document.body.style.background = 'black'
            document.body.style.color = 'white'

        } else {
            position = false
            document.body.style.background = 'white'
            document.body.style.color = 'black'

        }

    }


}
