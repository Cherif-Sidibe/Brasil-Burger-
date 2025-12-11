package sidibe.cherif.entity;

import java.time.LocalDate;
import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import lombok.ToString;

@Getter
@Setter
@ToString
@NoArgsConstructor
@AllArgsConstructor
public class Complement {
    private int id;
    private String nom;
    private TypeComplementEnum typeComplement;
    private String description;
    private String image;
    private double prix;
    private boolean isArchive;
    private LocalDate createAt;
    private LocalDate updateAt;

}
