package main;

/**
 * Created by NastyaVicodin on 3/18/2017.
 */
public class Order {
    public Shop shop;
    public Goods [] goods;
    public int id;
    public String title;
    public String orderDate;
    public String timeMin;
    public String timeMax;
    public String description;
    public int price;
    public Comment СlientComment;
    public Comment СourierComment;
    public Request[] requests;

    public Order(Shop shop, Goods[] goods, int id, String title, String orderDate, String timeMin, String timeMax,
                 String description, int price = 0, Comment clientComment = null, Comment courierComment = null, Request[] requests = null) {
        this.shop = shop;
        this.goods = goods;
        this.id = id;
        this.title = title;
        this.orderDate = orderDate;
        this.timeMin = timeMin;
        this.timeMax = timeMax;
        this.description = description;
        this.price = price;
        this.clientComment = clientComment;
        this.courierComment = courierComment;
        this.requests = requests;
    }

    public boolean addOrder();
    public static int getOrderById(int id);
    public boolean editOrder(int id);
    public static boolean isUsersOrder(int id);
    public boolean deleteOrderById(int id);
    public int countOrderRequests();



    public String getDescription() {
        return description;
    }

    public int getPrice() {
        return price;
    }

    public void setPrice(int price) {
        this.price = price;
    }

    public void setDescription(String description) {
        this.description = description;

    }

    public String getTitle() {
        return title;

    }

    public String getTimeMax() {
        return timeMax;
    }

    public void setTimeMax(String timeMax) {
        this.timeMax = timeMax;
    }

    public String getTimeMin() {
        return timeMin;

    }

    public void setTimeMin(String timeMin) {
        this.timeMin = timeMin;
    }

    public String getOrderDate() {

        return orderDate;
    }

    public void setOrderDate(String orderDate) {
        this.orderDate = orderDate;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public int getId() {

        return id;
    }

    public void setId(int id) {
        this.id = id;
    }



    public Shop getShop() {
        return shop;
    }

    public Goods[] getGoods() {
        return goods;
    }

    public void setGoods(Goods[] goods) {
        this.goods = goods;
    }

    public void setShop(Shop shop) {
        this.shop = shop;
    }

}
