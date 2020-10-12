const server = 'http://localhost:8083'

window.addEventListener('paste', async e => {
    const files = e.clipboardData.files
    if (files.length && files[0] instanceof File) {
        const image = files[0]
        await sendImage(image)
    }
});

/**
 * @param {File} image 
 * 
 * @returns {Promise}
 */
function sendImage(image) {
    return new Promise(resolve => {
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                resolve()
            }
        };
        xhttp.open('GET', server + '/upload', true);
        xhttp.send();
    })
}
