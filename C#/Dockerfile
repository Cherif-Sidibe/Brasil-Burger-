# Build stage
FROM mcr.microsoft.com/dotnet/sdk:9.0 AS build
WORKDIR /src

# Copy csproj and restore dependencies
COPY ["VueClient/VueClient.csproj", "VueClient/"]
RUN dotnet restore "VueClient/VueClient.csproj"

# Copy everything else and build
COPY . .
WORKDIR "/src/VueClient"
RUN dotnet build "VueClient.csproj" -c Release -o /app/build

# Publish stage
FROM build AS publish
RUN dotnet publish "VueClient.csproj" -c Release -o /app/publish /p:UseAppHost=false

# Runtime stage
FROM mcr.microsoft.com/dotnet/aspnet:9.0 AS final
WORKDIR /app
EXPOSE 8080
ENV ASPNETCORE_URLS=http://+:8080
COPY --from=publish /app/publish .
ENTRYPOINT ["dotnet", "VueClient.dll"]
