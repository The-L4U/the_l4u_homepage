const apiVersion = '7.13.1'

const getJSON = (url, callback) => {
    var xhr = new XMLHttpRequest()
    xhr.open('GET', url, true)
    xhr.responseType = 'json'
    xhr.onload = function () {
        var status = xhr.status
        if (status == 200) {
            callback(null, xhr.response)
        } else {
            callback(status)
        }
    }
    xhr.send()
}

// Return a string representing a members rank for given queue
const getMemebersRank = (member, type) => {
    const rank = member.rankstats.find(rank => rank.queueType === type)
    if(!rank) return 'Unranked'
    return `${rank.tier} ${rank.rank}`
}

// Return a nicely formatted and human readable date
const getDisplayDate = (memberDate) => {
    const date = new Date(memberDate)
    return `${date.getDate()}.${date.getMonth() + 1}.${date.getFullYear()}`
}

getJSON('php/data.json', (err, data) => {
    if(err) return console.error(data)
    let inner = ''
    data
        .sort((a,b) => a.l4urank > b.l4urank)
        .forEach(member => {
            inner += `
                <div class="mdc-card member">
                    <section class="mdc-card__media" style="background-image: url(http://ddragon.leagueoflegends.com/cdn/img/champion/splash/${member.matchobject.championName}_0.jpg)">
                        <section class="mdc-card__primary member__title">
                            <div class="member__summoner-icon" style="background-image: url(http://ddragon.leagueoflegends.com/cdn/${apiVersion}/img/profileicon/${member.summonerobject.profileIconId}.png )"></div>
                            <h1 class="mdc-card__title mdc-card__title--large">${member.l4uname}</h1>
                            <h2 class="mdc-card__subtitle">${member.task}</h2>
                        </section>
                    </section>
                    <section class="mdc-card__supporting-text">
                        <ul class="mdc-list">
                            <li class="mdc-list-item">Ingame: <a class="member__history-link" href="http://matchhistory.euw.leagueoflegends.com/en/#match-history/EUW1/${member.accountid}">${member.summonerobject.name}</a></li>
                            <li class="mdc-list-item">Solor Rank: ${getMemebersRank(member, 'RANKED_SOLO_5x5')}</li>
                            <li class="mdc-list-item">Flex Rank: ${getMemebersRank(member, 'RANKED_FLEX_SR')}</li>
                            <li class="mdc-list-item">Letztes Spiel: ${getDisplayDate(member.matchobject.createDate)}</li>
                        </ul>
                    </section>
                </div>`
        })
    document.getElementById('membersContainer').innerHTML = inner
})
