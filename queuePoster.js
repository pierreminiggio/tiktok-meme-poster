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
  * @returns {Promise}
  */
function postFirstTikTokInQueue() {

    return new Promise(async (resolve, rejects) => {

        const queueFile = './queue.json'
        const postedFile = './posted.json'

        fs.readFile(queueFile, 'utf8', (err, queueFileData) => {
            if (err) {
                rejects()
            }

            /** @type {TikTok[]} queue */
            const queue = JSON.parse(queueFileData)

            fs.readFile(postedFile, 'utf8', async (err, postedFileData) =>  {
                if (err) {
                    rejects()
                }

                /** @type {TikTok[]} posted */
                const posted = JSON.parse(postedFileData)

                if (queue.length) {
                    const key = 0
                    const toPost = queue[key]
                    await post(ids.login, ids.password, toPost.video, toPost.legend)
                    queue.splice(key, 1)
                    posted.push(toPost)
                    fs.writeFile(queueFile, JSON.stringify(queue), (err) => {
                        if (err) {
                            rejects()
                        }
                        fs.writeFile(postedFile, JSON.stringify(posted), (err) => {
                            if (err) {
                                rejects()
                            }
                            resolve()
                        })
                    })
                }
            })  
        })  
    })
}

module.exports = postFirstTikTokInQueue