// import QtQuick 1.0 // to target S60 5th Edition or Maemo 5
import QtQuick 1.0
import "menu/"

/*
  property string my_var0: "neco"  // bool real string
  qsTr("some text") used for translation
*/

Rectangle {
  id: win_main
  width: 1; height: 1;
  color: "#CC77FF"

  Column {
    anchors.centerIn: parent
    spacing: 10
    Text {
      anchors.horizontalCenter: parent.horizontalCenter
      color: "red"
      font.bold: true
      font.pixelSize: 80
      style: Text.Outline
      styleColor: "brown"
      //font.pointSize: 40.78
      text: "PAUSE"
      smooth: true
    }
    Text {
      anchors.horizontalCenter: parent.horizontalCenter
      smooth: true
      text: "press any keybord key or mouse button to play"
    }
  }

  //FIXME pridat bublinovou napovedu (ta by vsak byla na skodu -
  //  prekazela by - na mobilnich zarizenich
  MyMenu {
    id: menu_exit;
    anchors.bottom: parent.bottom
    anchors.left: parent.left
    width: 140; height: 140;
    icon: "menu_exit"
    onMouseClick: {
      console.log("exit button pressed from main window")
      Qt.quit();
    }
  }

  Text {
    //id: copyright_msg;
    //anchors.horizontalCenter: parent
    anchors.bottomMargin: 30
    anchors.bottom: parent.bottom
    anchors.horizontalCenter: parent.horizontalCenter
    smooth: true
    text: "(c) Radek, Lukas, Honza"
  }

  //FIXME rotace
  MyMenu {
    id: menu_main;
    anchors.bottom: parent.bottom
    anchors.right: parent.right
    width: 140; height: 140;
    icon: "menu_main"
  }

  // pozadi == zasedla fotka (stretch)
  // vlevo, uprostred, vpravo dole obrazky
  //   pruhlednost zachovana
  // uprostred (horizontalne, vertikalne) <bold red big pulsing text>PAUSE</><small black text>maly</>
  // mouse hover
  //   na ikony menu_exit/menu_main -> priblizeni/???
  // mouse click
  //   na obrazek -> zmizet (menu_exit + copyright + PAUSE)
  //   na ikony menu_exit/menu_main spusti animaci a otevre prislusna submenu
}

/*
Rectangle {
  id: flashingblob

  // value >0 mandatory (although not used because of fullscreen)
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
