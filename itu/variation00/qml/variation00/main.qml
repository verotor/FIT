// import QtQuick 1.0 // to target S60 5th Edition or Maemo 5
import QtQuick 1.1

// qsTr("some text") used for translation

//FIXME todo
//  PAUSE red bold text
//  bitmap background with random selection
//  buttons from icons (with transparency around non-transparent pixels)
//  animations
//    button hover
//    PAUSE text pulsing

// value >0 needed (although not used because of fullscreen)

Rectangle {
  id: flashingblob

  // value >0 needed (although not used because of fullscreen)
  width: 75; height: 75
  color: "blue"
  opacity: 1.0

  MouseArea {
    anchors.fill: parent
    onClicked: {
      animateColor.start()
      animateOpacity.start()
    }
  }

  PropertyAnimation {
    id: animateColor
    target: flashingblob
    properties: "color"; to: "green"; duration: 2000
  }

  NumberAnimation {
    id: animateOpacity
    target: flashingblob
    properties: "opacity"
    from: 0.99; to: 1.0
    loops: Animation.Infinite
    easing {
      type: Easing.OutBack
      overshoot: 500
    }
  }
}

/*
Rectangle {
  // value >0 needed (although not used because of fullscreen)
  width: 1; height: 1

  Row {
    anchors.centerIn: parent
    spacing: parent.width/6

    Text {
      text: "dalsi text"
      anchors.centerIn: parent
    }

    Text {
      //text: "<span foreground=\"red\" size=\"x-large\">PAUSE</span><br>" +
      text: "<span foreground=\"#FF0000\" size=\"50\">PAUSE</span><br>" +
      "press any keybord key or mouse button to play"
      anchors.centerIn: parent
    }
  }
  MouseArea {
    anchors.fill: parent
    onClicked: {
      Qt.quit();
    }
  }
}
*/

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
