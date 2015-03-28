/*import java.io.*;
import java.util.*;
import javax.servlet.*;
import javax.servlet.http.*;

public class weathersearch extends HttpServlet{
	public void doGet(HttpServletRequest request, HttpServletResponse response)
	throws IOException, ServletException
	{
		response.setContentType("text/html");
		PrintWriter out = response.getWriter();
		String type = request.getParameter("type");
		if("city".equals(type))
			out.println("<h1>This is a servlet test city</h1>");
		else
			out.println("<h1>This is a servlet test zip</h1>");
	}
}*/

import java.io.*;
import java.util.*;
import javax.servlet.*;
import javax.servlet.http.*;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.*;
import org.jdom.*;
import org.jdom.Document;
import org.jdom.Element;
import org.jdom.JDOMException;
import org.jdom.input.SAXBuilder;
import org.jdom.output.XMLOutputter;


public class weathersearch extends HttpServlet{
	public void doGet(HttpServletRequest request, HttpServletResponse response)
	throws IOException, ServletException
	{
		response.setContentType("text/html");
		String location = request.getParameter("location");
		String type = request.getParameter("type");
		String tempUnit = request.getParameter("tempUnit");
		//String result = location+type+tempUnit;
		PrintWriter out = response.getWriter();
		//out.println(result);
		String urlString = "http://cs-server.usc.edu:28204/homework_6.php?location="+location+"&type="+type+"&tempUnit="+tempUnit;
		
		//out.println(urlString);
		
		URL url = new URL(urlString);
		URLConnection urlConnection = url.openConnection();
		urlConnection.setAllowUserInteraction(false);
		InputStream urlStream = url.openStream();
		//read content 
		String temp;
		final StringBuffer yahoo = new StringBuffer();
		final BufferedReader in = new BufferedReader(new InputStreamReader(urlStream, "utf-8"));
		while ((temp = in.readLine()) != null)
		{
			yahoo.append(temp);
		}
		in.close();		
		out.println(yahoo);
		
		String xml=yahoo.toString();
		
		out.println(xml);
		
		try{
			SAXBuilder builder = new SAXBuilder();
			Document doc = builder.build(yahoo);
			out.println(doc);
			
			XMLOutputter fmt = new XMLOutputter();
			fmt.output(doc, System.out);
			
		}catch(Exception e){
		e.printStackTrace();
		}
	}
}
