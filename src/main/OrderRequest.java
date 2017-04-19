package main;

/**
 * Created by Dmitriy on 19.03.2017.
 */
public class OrderRequest extends Request {
    public String description;
    public int status;

    public orderRequest(String description, int status) {
        this.description = description;
        this.status = status;
    }

    public boolean addRequest(String orderId = null);
    public boolean deleteRequest(String orderId);
    public boolean confirmRequest(int requestId = null);
}
