const ids = require('./ids.json')
const post = require('@pierreminiggio/tiktok-poster')
const fs = require('fs')

/**
 * @typedef {Object} TikTok
 * 
 * @property {string} video
 * @property {string} legend
 */

 /**
  * @param {boolean} show
  * 
  * @returns {Promise}
  */
function postFirstTikTokInQueue(show = true) {

    return new Promise(async (resolve, reject) => {

        const queueFile = './queue.json'
        const postedFile = './posted.json'

        fs.readFile(queueFile, 'utf8', (err, queueFileData) => {
            if (err) {
                reject()
            }

            /** @type {TikTok[]} queue */
            const queue = JSON.parse(queueFileData)

            fs.readFile(postedFile, 'utf8', async (err, postedFileData) =>  {
                if (err) {
                    reject()
                }

                /** @type {TikTok[]} posted */
                const posted = JSON.parse(postedFileData)

                if (queue.length) {
                    const key = 0
                    const toPost = queue[key]
                    post(ids.login, ids.password, toPost.video, toPost.legend, show).then(() => {
                        queue.splice(key, 1)
                        posted.push(toPost)
                        fs.writeFile(queueFile, JSON.stringify(queue), (err) => {
                            if (err) {
                                reject()
                            }
                            fs.writeFile(postedFile, JSON.stringify(posted), (err) => {
                                if (err) {
                                    reject()
                                }
                                resolve()
                            })
                        })
                    }).catch(async (e) => {
                        console.log('Erreur: ' + e)
                        await postFirstTikTokInQueue(show)
                        resolve()
                    })
                } else if (posted.length) {
                    const toPost = posted[Math.floor(Math.random() * posted.length)]
                    post(ids.login, ids.password, toPost.video, toPost.legend, show).then(() => {
                        resolve()
                    }).catch(async (e) => {
                        console.log('Erreur: ' + e)
                        await postFirstTikTokInQueue(show)
                        resolve()
                    })
                } else {
                    reject('Nothing to post')
                }
            })  
        })  
    })
}

module.exports = postFirstTikTokInQueue