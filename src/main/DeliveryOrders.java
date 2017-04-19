package main;

/**
 * Created by NastyaVicodin on 3/18/2017.
 */
public class DeliveryOrders {
    public Order orders[];


    public Order[] getActiveOrders();
    public Order[] getUserOrders();
    public Order[] getHistoryOrders();
    public Order[] getUserOrdersInProcess();
    public int ordersCount();

    public Order[] getOrders() {
        return orders;
    }

    public void setOrders(Order[] orders) {
        this.orders = orders;
    }
}
