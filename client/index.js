const server = 'http://localhost:8083'

/** @type {HTMLTextAreaElement} */
const textArea = document.getElementById('legend')

/** @type {HTMLLabelElement} */
const label = document.getElementById('legend-label')

/** @type {HTMLDivElement} */
const loading = document.getElementById('loading')

/** @type {HTMLDivElement} */
const container = document.getElementById('container')

async function load() {
    await loadTikToks()
}

load()

window.addEventListener('paste', async e => {
    const files = e.clipboardData.files
    if (files.length && files[0] instanceof File) {

        startLoading()
        const image = files[0]
        const textResponse = await sendImage(image)
        if (textResponse) {
            const jsonResponse = JSON.parse(textResponse)
            if (jsonResponse.error) {
                alert(jsonResponse.error)
            } else {
                await saveTikTok(jsonResponse.video, textArea.value)
                await loadTikToks()
                endLoading()
            }
        }
    }
})

/**
 * @returns {Promise}
 */
function loadTikToks() {
    return new Promise(async resolve => {
        container.innerHTML = ''
        const textResponse = await getTikToks()
        const jsonResponse = JSON.parse(textResponse)
        let first = true
        if (jsonResponse.length) {
            container.innerHTML += '<h2>Queue</h2>'
        }
        jsonResponse.forEach(tikTok => {
            const tikTokDiv = document.createElement('DIV')
            tikTokDiv.style.padding = '15px'
            if (! first) {
                tikTokDiv.style.borderTop = '1px black solid'
            }
            tikTokDiv.innerHTML += '<div>Video : ' + tikTok.video + '</div><div>Légende : ' + tikTok.legend + '</div>'
            container.appendChild(tikTokDiv)
            first = false
        })
        resolve()
    })
}

/**
 * @returns {Promise}
 */
function getTikToks() {
    return new Promise(resolve => {
        const xhttp = new XMLHttpRequest()
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                resolve(this.responseText)
            }
        }
        xhttp.open('GET', server + '/get', true)
        xhttp.send()
    })
}

/**
 * @param {File} image 
 * 
 * @returns {Promise}
 */
function sendImage(image) {
    return new Promise(resolve => {
        const xhttp = new XMLHttpRequest()
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                resolve(this.responseText)
            }
        }
        xhttp.open('POST', server + '/upload', true)
        const formData = new FormData()
        formData.append('image', image)
        xhttp.send(formData)
    })
}

/**
 * @param {string} video 
 * @param {string} legend 
 * 
 * @returns {Promise}
 */
function saveTikTok(video, legend) {
    return new Promise(resolve => {
        const xhttp = new XMLHttpRequest()
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                resolve(this.responseText)
            }
        }
        xhttp.open('POST', server + '/save', true)
        const formData = new FormData()
        formData.append('video', video)
        formData.append('legend', legend)
        xhttp.send(formData)
    })
}

function startLoading()
{
    textArea.style.display = 'none'
    label.style.display = 'none'
    loading.style.display = 'block'
}

function endLoading()
{
    textArea.style.display = 'block'
    label.style.display = 'block'
    loading.style.display = 'none'
}