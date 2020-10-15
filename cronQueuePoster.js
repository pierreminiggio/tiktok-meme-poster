const postFirstTikTokInQueue = require('./queuePoster.js')
const cron = require('node-cron')

cron.schedule('0 * * * *', () => {
    postFirstTikTokInQueue()
})
