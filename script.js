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

getJSON('php/data.json', (err, data) => {
    if(err) return console.error(data)
    let inner = ''
    data
        .sort((a,b) => a.l4urank > b.l4urank)
        .forEach(member => {
            inner += `
                <div class="mdc-card member">
                    <section class="mdc-card__media" style="background-image: url(${member.matchobject.championId})"></section>
                    <section class="mdc-card__primary">
                        <div class="member__summoner-icon" style="background-image: url(http://ddragon.leagueoflegends.com/cdn/7.13.1/img/profileicon/${member.summonerobject.profileIconId}.png )"></div>
                        <h1 class="mdc-card__title mdc-card__title--large">${member.l4uname}</h1>
                        <h2 class="mdc-card__subtitle">${member.task}</h2>
                    </section>
                </div>`
        })
    document.getElementById('membersContainer').innerHTML = inner
})
