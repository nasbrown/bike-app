/*
    var popup = L.popup()
    .setLatLng(latlng)
    .setContent('<p>Hello world!<br />This is a nice popup.</p>')
    .openOn(map);

    var popup = L.popup(latlng, {content: '<p>Hello world!<br />This is a nice popup.</p>'})
    .openOn(map);

    <form method="POST" class="form-db" enctype="multipart/form-data">
                    <label>Take your picture</label>
                    <button type="submit">Submit</button>
                </form>
*/

let map = L.map('map', {center: ['42.0988', '-75.9206'], zoom: 15})
let coordArr = []


L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map)


map.on('click', (e) =>{

let coordinates = [e.latlng.lat, e.latlng.lng]

let marker = myNewMarker(coordinates, map)

mynewPopup(coordinates, insertFormHtml(), map)

coordArr.push(marker)

if(coordArr.length >= 2){
    console.log('Remove')
    map.removeLayer(coordArr[0])
    coordArr.shift(coordArr[0])
    console.log(coordArr)
    return
} 
})

document.addEventListener('submit', async(e) => {
    if(e.target.id === 'form-save-db'){
        let bikeForm = document.getElementById('form-save-db')
        e.preventDefault()

        let bikeLocationFormData = new FormData(bikeForm);
        console.log(bikeLocationFormData)
    }
})

const myNewMarker = (coordinates = [], theMap = {}) => { //factory functions
    return L.marker(coordinates).addTo(theMap)
}

const mynewPopup = (coordinates, html = '', theMap= {}) => { //factory functions
    return L.popup().setLatLng(coordinates).setContent(html).openOn(theMap)
}

const insertFormHtml = () => {
    return `<form method="POST" id="form-save-db" enctype="multipart/form-data">
                    <div>
                        <label for="image-file">Take your picture</label>
                    </div>
                    <div>
                        <input type="file" id="image-file" name="image-file">
                    </div>
                    <div>
                        <label for="loc-name">Location Name:</label>
                    </div>
                    <div>
                        <input type="text" id="loc-name" name="loc-name">
                    </div>
                    <button type="submit">Submit</button>
                </form>`
}
