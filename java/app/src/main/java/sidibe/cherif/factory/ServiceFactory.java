package sidibe.cherif.factory;

import sidibe.cherif.repository.*;
import sidibe.cherif.repository.impl.*;
import sidibe.cherif.service.*;
import sidibe.cherif.service.impl.*;


public class ServiceFactory {
    
    private static ServiceFactory instance;
    
    private BurgerRepository burgerRepository;
    private MenuRepository menuRepository;
    private ComplementRepository complementRepository;
    private ZoneRepository zoneRepository;
    private UserRepository userRepository;
    
    private BurgerService burgerService;
    private MenuService menuService;
    private ComplementService complementService;
    private ZoneService zoneService;
    private UserService userService;
    private ImageService imageService;
    
    
    private ServiceFactory() {
        initializeRepositories();
        initializeServices();
    }
    
    
    public static ServiceFactory getInstance() {
        if (instance == null) {
            instance = new ServiceFactory();
        }
        return instance;
    }
    
    
    private void initializeRepositories() {
        burgerRepository = new BurgerRepositoryImpl();
        menuRepository = new MenuRepositoryImpl();
        complementRepository = new ComplementRepositoryImpl();
        zoneRepository = new ZoneRepositoryImpl();
        userRepository = new UserRepositoryImpl();
    }
    
    
    private void initializeServices() {
        burgerService = new BurgerServiceImpl(burgerRepository);
        menuService = new MenuServiceImpl(menuRepository);
        complementService = new ComplementServiceImpl(complementRepository);
        zoneService = new ZoneServiceImpl(zoneRepository);
        userService = new UserServiceImpl(userRepository);
        imageService = new ImageServiceImpl();
    }
    
    
    public BurgerService getBurgerService() {
        return burgerService;
    }
    
    public MenuService getMenuService() {
        return menuService;
    }
    
    public ComplementService getComplementService() {
        return complementService;
    }
    
    public ZoneService getZoneService() {
        return zoneService;
    }
    
    public UserService getUserService() {
        return userService;
    }
    
    public ImageService getImageService() {
        return imageService;
    }
    
    
    public BurgerRepository getBurgerRepository() {
        return burgerRepository;
    }
    
    public MenuRepository getMenuRepository() {
        return menuRepository;
    }
    
    public ComplementRepository getComplementRepository() {
        return complementRepository;
    }
    
    public ZoneRepository getZoneRepository() {
        return zoneRepository;
    }
    
    public UserRepository getUserRepository() {
        return userRepository;
    }
}
