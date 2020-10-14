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

        /** @type {TikTok[]} queue */
        const queue = require(queueFile)

        /** @type {TikTok[]} posted */
        const posted = require(postedFile)

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
}

postFirstTikTokInQueue()
