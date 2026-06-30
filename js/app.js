

let map = L.map('map', {center: ['42.0988', '-75.9206'], zoom: 15})
let coordArr = []

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map)


map.on('click', (e) =>{

let coordinates = [e.latlng.lat, e.latlng.lng]

let myNewMarker = L.marker(coordinates).addTo(map)

coordArr.push(myNewMarker)

if(coordArr.length >= 2){
    console.log('Remove')
    map.removeLayer(coordArr[0])
    coordArr.shift(coordArr[0])
    console.log(coordinates)
    return
}
     
})
