// import QtQuick 1.0 // to target S60 5th Edition or Maemo 5
import QtQuick 1.1

Rectangle {
  width: 1
  height: 1
  Text {
    text: "PAUSE<br>press any keybord key or mouse button to play"
    anchors.centerIn: parent
  }
  MouseArea {
    anchors.fill: parent
    onClicked: {
      Qt.quit();
    }
  }
}
