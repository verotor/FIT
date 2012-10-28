// import QtQuick 1.0 // to target S60 5th Edition or Maemo 5
import QtQuick 1.1

//FIXME todo
//  fullscreen
//  PAUSE red bold text
//  bitmap background with random selection
//  buttons from icons (with transparency around non-transparent pixels)
//  animations
//    button hover
//    PAUSE text pulsing

Rectangle {
  //width: 5  //getScreenWidth()
  //height: 5  //getScreenHeight()
  Text {
    text: qsTr("<b>PAUSE</b>")
    anchors.centerIn: parent
  }
  MouseArea {
    anchors.fill: parent
    onClicked: {
      Qt.quit();
    }
  }
}

/*
Rectangle{
  id:fullScreen
  width: fullScreenImage.width
  height: fullScreenImage.height
  color: "transparent"
  x:0;y:50;
  Image{
    id:fullScreenImage
    source: "pics/fullScreenDown.png"
  }
  MouseArea{
    id:fullScreenArea
    anchors.fill: fullScreenImage
    onPressed: {
      fscreen=1;
      fullScreenImage.source="pics/fullScreenUp.png"
      showhanddown(3)
      fullscreen(k)
      //playIcon.x=250
      //playIcon.y=200
    }
    onReleased: {
      fullScreenImage.source="pics/fullScreenDown.png"
      showhanddown(0)
    }
  }
}
*/

// vim: set ft=javascript:
