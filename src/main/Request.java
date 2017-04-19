package main;

/**
 * Created by NastyaVicodin on 3/18/2017.
 */
public abstract class Request {
    public int id;
    public int orderId;
    public int userId;
    public String timePosted;

    public Request(int id, int orderId, int userId, String timePosted) {
        this.id = id;
        this.orderId = orderId;
        this.userId = userId;
        this.timePosted = timePosted;
    }

    public Request getRequestById(int id);
    public Request getRequestByOrderId(ind orderId);
    public static boolean hasUserRequest(int orderId);
    public boolean confirmRequest(int requestId = null);

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getOrderId() {
        return orderId;
    }

    public void setOrderId(int orderId) {
        this.orderId = orderId;
    }

    public int getUserId() {
        return userId;
    }

    public void setUserId(int userId) {
        this.userId = userId;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getTimePosted() {
        return timePosted;
    }

    public void setTimePosted(String timePosted) {
        this.timePosted = timePosted;
    }

    public int getStatus() {
        return status;
    }

    public void setStatus(int status) {
        this.status = status;
    }
}
