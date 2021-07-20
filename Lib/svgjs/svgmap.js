// SVG Map Toolkit - Script Library 2007/12/10
// Copyright (C) SVGMap Consortium.

function SVGMap(element)
{
	var m_element = element;
	var m_overlays = new Array();
	var m_svg;
	var m_savedRotate;
	var m_savedScale;
	var m_savedCenter;
	//debugger;
	try
	{
		m_svg = m_element.getSVGDocument().documentElement;
		//debugger;
	}
	catch (e)
	{
		//debugger;
		m_svg = m_element.contentDocument.documentElement;
		// debugger;
	}
	var m_geoToSvg = SVGMapImpl.getCrsTransform(m_svg);
	
	this.getCenter = function()
	{
		return this.fromContainerPixelToLatLng(
			new SVGPoint(m_element.offsetWidth / 2, m_element.offsetHeight / 2)
			);
	};
	this.getBounds = function ()
	{
		return new SVGLatLngBounds(
			this.fromContainerPixelToLatLng(new SVGPoint(0, m_element.offsetHeight)),
			this.fromContainerPixelToLatLng(new SVGPoint(m_element.offsetWidth, 0))
			);
	}
	this.getSize = function()
	{
		return new SVGSize(m_element.offsetWidth, m_element.offsetHeight);
	}
	this.getZoom = function()
	{
		return SVGMapImpl.round(m_svg.currentScale);
	}
	this.setCenter = function(point, zoom)
	{
		var suspendHandle = m_svg.suspendRedraw(10000);
		//debugger;
		try
		{
			if (zoom != undefined)
			{
				m_svg.currentScale = zoom;
			}
			// get viewport coordinates of the point.
			var svgToViewport = m_svg.getScreenCTM();
			var geoToViewport = svgToViewport.multiply(m_geoToSvg);
			var vX = geoToViewport.a * point.lng() + geoToViewport.c * point.lat() + geoToViewport.e;
			var vY = geoToViewport.b * point.lng() + geoToViewport.d * point.lat() + geoToViewport.f;
			// translate user transform so as to move the point to the center of viewport.
			m_svg.currentTranslate.x += m_element.offsetWidth / 2 - vX;
			m_svg.currentTranslate.y += m_element.offsetHeight / 2 - vY;
		}
		finally
		{
			m_svg.unsuspendRedraw(suspendHandle);
		}
	};
	this.panTo = function (center)
	{
		this.setCenter(center);
	}
	this.panBy = function (distance)
	{
		var suspendHandle = m_svg.suspendRedraw(10000);
		try
		{
			m_svg.currentTranslate.x += distance.width;
			m_svg.currentTranslate.y += distance.height;
		}
		finally
		{
			m_svg.unsuspendRedraw(suspendHandle);
		}
	}
	this.panDirection = function (dx, dy)
	{
		this.panBy(new SVGSize(
			m_element.offsetWidth / 2 * dx, m_element.offsetHeight / 2 * dy
			));
	}
	this.setZoom = function (level)
	{
		var oldCenter = this.getCenter();
		// changing currentScale makes no effect on currentTranslate.
		// so, to preserve center view point, currentTranslate also has to be adjusted.
		this.setCenter(oldCenter, level);
	}
	this.zoomIn = function ()
	{
		this.setZoom(m_svg.currentScale * 1.5);
	}
	this.zoomOut = function ()
	{
		this.setZoom(m_svg.currentScale / 1.5);
		
	}
	this.setRotate = function (angle)
	{
		var suspendHandle = m_svg.suspendRedraw(10000);
		//debugger;
		try
		{
			var oldCenter = this.getCenter();
			m_svg.currentRotate = angle;
			this.setCenter(oldCenter);
		}
		finally
		{
			m_svg.unsuspendRedraw(suspendHandle);
		}
	}
	this.getRotate = function ()
	{
		return SVGMapImpl.round(m_svg.currentRotate);
	}
	this.savePosition = function ()
	{
		m_savedRotate = m_svg.currentRotate;
		m_savedScale = m_svg.currentScale;
		m_savedCenter = this.getCenter();
	}
	this.returnToSavedPosition = function()
	{
		var suspendHandle = m_svg.suspendRedraw(10000);
		try
		{
			m_svg.currentRotate = m_savedRotate;
			this.setCenter(m_savedCenter, m_savedScale);
		}
		finally
		{
			m_svg.unsuspendRedraw(suspendHandle);
		}
	}
	this.fromContainerPixelToLatLng = function(point)
	{
		var svgToViewport = m_svg.getScreenCTM();
		var geoToViewport = svgToViewport.multiply(m_geoToSvg);
		var viewportToGeo = geoToViewport.inverse();
		return new SVGLatLng(
			viewportToGeo.b * point.x + viewportToGeo.d * point.y + viewportToGeo.f,
			viewportToGeo.a * point.x + viewportToGeo.c * point.y + viewportToGeo.e
			);
	}
	this.fromLatLngToContainerPixel = function(latlng)
	{
		var svgToViewport = m_svg.getScreenCTM();
		var geoToViewport = svgToViewport.multiply(m_geoToSvg);
		return new SVGPoint(
			geoToViewport.a * latlng.lng() + geoToViewport.c * latlng.lat() + geoToViewport.e,
			geoToViewport.b * latlng.lng() + geoToViewport.d * latlng.lat() + geoToViewport.f
			);
	}
	this.addOverlay = function (overlay)
	{
		var suspendHandle = m_svg.suspendRedraw(10000);
		try
		{
			overlay.initialize(this);
			m_overlays.push(overlay);
		}
		finally
		{
			m_svg.unsuspendRedraw(suspendHandle);
		}
	}
	this.removeOverlay = function (overlay)
	{
		var suspendHandle = m_svg.suspendRedraw(10000);
		try
		{
			for (var i = 0; i < m_overlays.length; ++i)
			{
				if (m_overlays[i] == overlay)
				{
					m_overlays[i].remove();
					m_overlays.splice(i, 1);
					return;
				}
			}
			throw new Error("the overlay cannot be found");
		}
		finally
		{
			m_svg.unsuspendRedraw(suspendHandle);
		}
	}
	this.clearOverlays = function ()
	{
		var suspendHandle = m_svg.suspendRedraw(10000);
		try
		{
			while (m_overlays.length > 0)
			{
				m_overlays[m_overlays.length - 1].remove();
				m_overlays.splice(m_overlays.length - 1, 1);
			}
		}
		finally
		{
			m_svg.unsuspendRedraw(suspendHandle);
		}
	}
	this.getSVGDocument = function ()
	{
		return m_svg.ownerDocument;
	}
}

function SVGLayerOverlay(url)
{
	var m_url = url;
	var m_doc = null;
	var m_holder = null;
	this.initialize = function (map)
	{
		if (m_doc)
		{
			throw new Error("this overlay is already added");
		}
		m_doc = map.getSVGDocument().implementation.createDocument(null, null, null);
		m_doc.async = false;
		m_doc.load(m_url);
		try
		{
			map.getSVGDocument().appendDocumentChild(m_doc);
		}
		catch (e)
		{
			var geoToSvgChild = SVGMapImpl.getCrsTransform(m_doc.documentElement);
			var geoToSvgParent = SVGMapImpl.getCrsTransform(map.getSVGDocument().documentElement);
			var crsAdjust = geoToSvgParent.multiply(geoToSvgChild.inverse());
			m_holder = map.getSVGDocument().createElementNS("http://www.w3.org/2000/svg", "g");
			m_holder.setAttribute("transform", SVGMapImpl.makeTransformText(crsAdjust));
			m_holder = map.getSVGDocument().documentElement.appendChild(m_holder);
			for (var node = m_doc.documentElement.firstChild; node != null; node = node.nextSibling)
			{
				var clone = map.getSVGDocument().importNode(node, true);
				m_holder.appendChild(clone);
			}
		}
	}
	this.remove = function ()
	{
		if (!m_doc)
		{
			return;
		}
		if (m_holder)
		{
			m_holder.parentNode.removeChild(m_holder);
			m_holder = null;
		}
		else
		{
			m_doc.parentDocument.removeDocumentChild(m_doc);
		}
		m_doc = null;
	}
	this.copy = function ()
	{
		return new SVGLayerOverlay(m_url);
	}
}

function SVGSize(width, height)
{
	this.width = width;
	this.height = height;
	this.equals = function (other)
	{
		return this.width == other.width && this.height == other.height;
	}
	this.toString = function ()
	{
		return this.width + "," + this.height;
	}
}

function SVGPoint(x, y)
{
	this.x = x;
	this.y = y;
	this.equals = function (other)
	{
		return this.x == other.x && this.y == other.y;
	}
	this.toString = function ()
	{
		return this.x + "," + this.y;
	}
}

function SVGLatLng(lat, lng)
{
	// TODO: overflow
	var m_lat = lat;
	var m_lng = lng;
	this.lat = function ()
	{
		return SVGMapImpl.round(m_lat);
	}
	this.lng = function ()
	{
		return SVGMapImpl.round(m_lng);
	}
	this.equals = function (other)
	{
		return Math.abs(m_lat - other.lat()) < 0.000000001 
			&& Math.abs(m_lng - other.lng()) < 0.000000001;
	}
	this.toString = function ()
	{
		return this.lat() + "," + this.lng();
	}
}

function SVGLatLngBounds(sw, ne)
{
	// TODO: overflow
	var m_sw = sw;
	var m_ne = ne;
	this.equals = function (other)
	{
		return m_sw.equals(other.getSouthWest())
			&& m_ne.equals(other.getNorthEast());
	}
	this.toString = function ()
	{
		return m_sw.toString() + "," + m_ne.toString();
	}
	this.contains = function (latlng)
	{
		return m_sw.lng() <= latlng.lng() && latlng.lng() <= m_ne.lng()
			&& m_sw.lat() <= latlng.lat() && latlng.lat() <= m_ne.lat();
	}
	this.intersects = function (other)
	{
		return m_sw.lat() <= other._ne.lat()
			&& m_ne.lat() >= other._sw.lat()
			&& m_sw.lng() <= other._ne.lng()
			&& m_ne.lng() >= other._sw.lng();
	}
	this.containsBounds = function (other)
	{
		return m_sw.lat() <= other._sw.lat()
			&& m_ne.lat() >= other._ne.lat()
			&& m_sw.lng() <= other._sw.lng()
			&& m_ne.lng() >= other._ne.lng();
	}
	this.getSouthWest = function (other)
	{
		return m_sw;
	}
	this.getNorthEast = function (other)
	{
		return m_ne;
	}
}

function SVGMapImpl()
{
}

{
	SVGMapImpl.round = function (x)
	{
		return Math.floor(x * 100000000 + 0.5) / 100000000;
	}
	SVGMapImpl.makeTransformText = function (matrix)
	{
		return "matrix(" + matrix.a + "," + matrix.b + "," + matrix.c + "," + matrix.d + "," + matrix.e + "," + matrix.f + ")";
	}
	SVGMapImpl.getCrsTransform = function (svg)
	{
		var crs =svg.getElementsByTagNameNS("http://www.ogc.org/crs", "CoordinateReferenceSystem").item(0);
		if (!crs)
		{
			return svg.createSVGMatrix();
		}
		var str = crs.getAttributeNS("http://www.w3.org/2000/svg", "transform");
		if (!str)
		{
			return svg.createSVGMatrix();
		}
		return SVGMapImpl.parseTransform(svg, str);
	}
	SVGMapImpl.parseTransform = function (svg, str)
	{
		try
		{
			if (str.match(/^\s*none\s*$/))
			{
				return svg.createSVGMatrix();
			}
			else
			{
				return SVGMapImpl.parseTransformList(svg, str);
			}
		}
		catch (e)
		{
			// conforming SVG specification, an invalid transform text should be
			// treated as identity matrix.
			return svg.createSVGMatrix();
		}
	}
	SVGMapImpl.parseTransformList = function (svg, str)
	{
		var matrix = svg.createSVGMatrix();
		var mr;
		while (mr = str.match(/^\s*(.+)$/))
		{
			var ret = SVGMapImpl.parseTransformListElement(svg, mr[1]);
			str = ret.str;
			matrix = ret.matrix.multiply(matrix);
			// parse delimiter
			mr = str.match(/^\s*,(.*)$/);
			if (mr)
			{
				str = mr[1];
			}
		}
		return matrix;
	}
	SVGMapImpl.parseTransformListElement = function (svg, str)
	{
		var matrix = svg.createSVGMatrix();
		mr = str.match(/^matrix\(\s*([0-9\+\-\.Ee]*)\s*,?\s*([0-9\+\-\.Ee]*)\s*,?\s*([0-9\+\-\.Ee]*)\s*,?\s*([0-9\+\-\.Ee]*)\s*,?\s*([0-9\+\-\.Ee]*)\s*,?\s*([0-9\+\-\.Ee]*)\s*\)(.*)$/);
		if (mr)
		{
			matrix.a = mr[1];
			matrix.b = mr[2];
			matrix.c = mr[3];
			matrix.d = mr[4];
			matrix.e = mr[5];
			matrix.f = mr[6];
			return { matrix: matrix, str: mr[7] };
		}
		mr = str.match(/^translate\(\s*([0-9\+\-\.Ee]*)\s*,?\s*([0-9\+\-\.Ee]*)?\s*\)(.*)$/);
		if (mr)
		{
			matrix.a = 1;
			matrix.b = 0;
			matrix.c = 0;
			matrix.d = 1;
			matrix.e = mr[1];
			matrix.f = mr[2] != "" ? mr[2] : 0;
			return { matrix: matrix, str: mr[3] };
		}
		mr = str.match(/^scale\(\s*([0-9\+\-\.Ee]*)\s*,?\s*([0-9\+\-\.Ee]*)?\s*\)(.*)$/);
		if (mr)
		{
			matrix.a = mr[1];
			matrix.b = 0;
			matrix.c = 0;
			matrix.d = mr[2] != "" ? mr[2] : mr[1];
			matrix.e = 0;
			matrix.f = 0;
			return { matrix: matrix, str: mr[3] };
		}
		mr = str.match(/^rotate\(\s*([0-9\+\-\.Ee]*)\s*,?\s*([0-9\+\-\.Ee]*)?\s*,?\s*([0-9\+\-\.Ee]*)?\s*\)(.*)$/);
		if (mr)
		{
			var rad = mr[1] * Math.acos(-1) / 180;
			matrix.a = Math.cos(rad);
			matrix.b = Math.sin(rad);
			matrix.c = -Math.sin(rad);
			matrix.d = Math.cos(rad);
			matrix.e = 0;
			matrix.f = 0;
			if (mr[2] == "" && mr[3] == "")
			{
				return { matrix: matrix, str: mr[4] };
			}
			if (mr[2] == "" || mr[3] == "")
			{
				throw new Error("invalid transform-list rotate parameter");
			}
			var translate = svg.createSVGMatrix();
			translate.a = 1;
			translate.b = 0;
			translate.c = 0;
			translate.d = 1;
			translate.e = mr[2];
			translate.f = mr[3];
			return { matrix: translate.multiply(matrix).multiply(translate.inverse()), str: mr[4] };
		}
		mr = str.match(/^skewX\(\s*([0-9\+\-\.Ee]*)\s*\)(.*)$/);
		if (mr)
		{
			matrix.a = 1;
			matrix.b = 0;
			matrix.c = Math.tan(mr[1] * Math.acos(-1) / 180);
			matrix.d = 1;
			matrix.e = 0;
			matrix.f = 0;
			return { matrix: matrix, str: mr[2] };
		}
		mr = str.match(/^skewY\(\s*([0-9\+\-\.Ee]*)\s*\)(.*)$/);
		if (mr)
		{
			matrix.a = 1;
			matrix.b = Math.tan(mr[1] * Math.acos(-1) / 180);
			matrix.c = 0;
			matrix.d = 1;
			matrix.e = 0;
			matrix.f = 0;
			return { matrix: matrix, str: mr[2] };
		}
		throw new Error("invalid transform-list element");
	}
}
