export function shortText() {
    let arr = document.querySelectorAll('.description , .single_description')

    let array = []
    arr.forEach(function (elem) {
        array.push(elem.innerHTML)
        for (let i = 0; i < array.length; i++) {
            let newArr = array[i].split('')
            let n = newArr.slice(0, 100)
            elem.innerHTML = n.join('') + '...'
        }
    })
}


