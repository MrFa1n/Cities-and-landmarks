{"version":3,"file":"gauge.min.js","sources":["gauge.js"],"names":["AmCharts","GaugeAxis","Class","construct","this","radius","startAngle","endAngle","startValue","endValue","valueInterval","minorTickInterval","tickLength","minorTickLength","tickColor","labelFrequency","tickThickness","tickAlpha","inside","labelOffset","showLastLabel","showFirstLabel","axisThickness","axisColor","axisAlpha","gridInside","topText","topTextYOffset","topTextBold","bottomText","bottomTextYOffset","bottomTextBold","centerY","centerX","bandOutlineAlpha","bandOutlineThickness","bandOutlineColor","bandAlpha","value2angle","a","singleValueAngle","setTopText","b","chart","axisCreated","topTF","remove","d","topTextFontSize","fontSize","c","topTextColor","color","text","container","fontFamily","translate","centerXReal","centerYReal","radiusReal","graphsSet","push","setBottomText","bottomTF","bottomTextFontSize","bottomTextColor","draw","l","g","h","k","f","m","e","p","u","B","C","D","E","F","q","w","toCoordinate","realWidth","x","realHeight","s","fill","fill-opacity","stroke-width","stroke-opacity","n","z","r","wedge","doNothing","isModern","Math","round","v","sin","PI","t","cos","A","y","line","isNaN","bands","length","innerRadius","outlineColor","outlineThickness","outlineAlpha","alpha","stroke","setAttr","gridSet","getBBox","width","height","GaugeArrow","nailAlpha","startWidth","nailRadius","borderAlpha","nailBorderAlpha","nailBorderThickness","frame","setValue","previousValue","value","GaugeBand","AmAngularGauge","inherits","AmChart","base","call","minRadius","marginRight","marginBottom","marginTop","marginLeft","faceColor","faceAlpha","faceBorderWidth","faceBorderColor","faceBorderAlpha","arrows","axes","startDuration","startEffect","adjustSize","extraHeight","extraWidth","clockWiseOnly","addAxis","initChart","axis","drawChart","totalFrames","updateRate","updateWidth","updateHeight","getTitleHeight","min","gaugeX","gaugeY","circle","toBack","chartCreated","validateNow","validateSize","addArrow","removeArrow","removeFromArray","removeAxis","drawArrow","set","nailColor","polygon","updateAnimations","getEffect"],"mappings":"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"}