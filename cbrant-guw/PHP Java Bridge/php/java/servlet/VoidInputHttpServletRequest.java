/**
 * 
 */
package php.java.servlet;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;

import jakarta.servlet.ReadListener;
import jakarta.servlet.ServletInputStream;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletRequestWrapper;

/**
 * A simple HTTP servlet request which is not connected to any input stream.
 * 
 * @author jostb
 */
public class VoidInputHttpServletRequest extends HttpServletRequestWrapper {

    public VoidInputHttpServletRequest(HttpServletRequest req) {
	super(req);
    }

    private ServletInputStream in = null;
    public ServletInputStream getInputStream() {
	if (in != null) return in;
	return in = new ServletInputStream() {
            public int read() throws IOException {
        	return -1;
            }

			public boolean isFinished() {
				// TODO Auto-generated method stub
				return false;
			}

			public boolean isReady() {
				// TODO Auto-generated method stub
				return false;
			}

			public void setReadListener(ReadListener arg0) {
				// TODO Auto-generated method stub
				
			}
	};
    }
    private BufferedReader reader = null;
    public BufferedReader getReaader () {
	if (reader != null) return reader;
	return reader = new BufferedReader(new InputStreamReader(getInputStream()));
    }
}