export function dateFormat(date) {
    var fmt = date.getFullYear() + '-'
    fmt += ('0' + (date.getMonth() + 1)).substr(-2) + '-'
    fmt += ('0' + date.getDate()).substr(-2)
    return fmt
}

export function getData(url, router) {

}

export function postData(url, data) {

}
