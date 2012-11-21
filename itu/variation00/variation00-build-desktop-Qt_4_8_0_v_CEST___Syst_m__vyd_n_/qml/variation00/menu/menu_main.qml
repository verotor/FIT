// import QtQuick 1.0 // to target S60 5th Edition or Maemo 5
import QtQuick 1.0

BorderImage {
  id: __menu

  property string icon: "menu_dummy.png"
  //property alias operation: buttonText.text

  signal clicked

  source: "img_menu/" + icon;
  clip: true
  border { left: 10; top: 10; right: 10; bottom: 10 }

  Rectangle {
    id: shade
    anchors.fill: button
    radius: 10
    color: "black"
    opacity: 0
  }

  function do_nothing (abc) { console.log(abc) }

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
      do_nothing("menu" + icon)
      menu.clicked()
    }
  }

  states: State {
    name: "pressed"
    when: mouseArea.pressed == true
    PropertyChanges { target: shade; opacity: .4 }
  }
}