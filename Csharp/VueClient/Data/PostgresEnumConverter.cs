using Microsoft.EntityFrameworkCore.Storage.ValueConversion;

namespace VueClient.Data;

public class PostgresEnumConverter<TEnum> : ValueConverter<TEnum, string> where TEnum : struct, Enum
{
    public PostgresEnumConverter() : base(
        v => v.ToString(),
        v => Enum.Parse<TEnum>(v))
    {
    }
}
