const postFirstTikTokInQueue = require('./queuePoster.js')
const cron = require('node-cron')

cron.schedule('0 * * * *', async () => {
    console.log(new Date().toLocaleTimeString())
    console.log('Try posting...')
    await postFirstTikTokInQueue()
    console.log('Posted !')
})
