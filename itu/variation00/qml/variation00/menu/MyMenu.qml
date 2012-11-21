// import QtQuick 1.0 // to target S60 5th Edition or Maemo 5
import QtQuick 1.0

//FIXME transparency needed!!!
Item {
  id: myMenu
  property string icon: "dummy"
  //property alias operation: buttonText.text
  signal mouseClick

  Rectangle {
    id: shade
    anchors.fill: parent
    radius: image.width
    color: "black"
    opacity: 0
  }

  //FIXME animace "pomale pulsovani"
  Image {
    id: image
    anchors {
      fill: parent;
      //fillMode: "PreserveAspectFit"
      topMargin: 16; bottomMargin: 16; leftMargin: 16; rightMargin: 16
    }
    //z: 2
    smooth: true
    source: "../../../img/menu/" + icon + "0.png"
    clip: false
  }

  function clicked(abc) { console.log("clicked(): " + abc) }

  MouseArea {
    id: mouseArea
    anchors.fill: parent
    hoverEnabled: true
    //onClicked: { clicked("zdarec") }
    onClicked: mouseClick()
  }

  /* only the first matching state (according to "when") is chosen */
  states: [
    State {
      name: "pressed"
      when: mouseArea.pressed == true
      PropertyChanges { target: shade; opacity: .4 }
      PropertyChanges {
        target: image; source: "../../../img/menu/" + icon + "0.png"
      }
    },
    State {
      name: "mouse_onEntered"
      when: mouseArea.containsMouse == true
      PropertyChanges {
        target: image; source: "../../../img/menu/" + icon + "1.png"
      }
    }
  ]
}