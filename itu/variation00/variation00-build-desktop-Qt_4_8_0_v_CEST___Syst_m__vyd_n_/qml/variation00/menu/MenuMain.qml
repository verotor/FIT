// import QtQuick 1.0 // to target S60 5th Edition or Maemo 5
import QtQuick 1.0

/*
Image {
  id: img
  anchors.centerIn: parent;
  width: 400
  fillMode: "PreserveAspectFit"
  z: 2
}
*/

BorderImage {
  id: __menu

  property string icon: "menu_dummy.png"
  //property alias operation: buttonText.text

  //signal clicked

  source: "../../../img/menu/" + icon;
  clip: true
  //border { left: 20; top: 20; right: 20; bottom: 20 }

  Rectangle {
    id: shade
    anchors.fill: __menu
    radius: 10
    color: "black"
    opacity: 0
  }

  function clicked(abc) { console.log("clicked(): " + abc) }

  //popisky
  //Text {
  //  id: buttonText
  //  anchors.centerIn: parent; anchors.verticalCenterOffset: -1
  //  font.pixelSize: parent.width > parent.height ? parent.height * .5 : parent.width * .5
  //  style: Text.Sunken; color: "white"; styleColor: "black"; smooth: true
  //}

  MouseArea {
    id: mouseArea
    anchors.fill: parent
    onClicked: {
      __menu.clicked()
    }
  }

  states: State {
    name: "pressed"
    when: mouseArea.pressed == true
    PropertyChanges { target: shade; opacity: .4 }
  }
}